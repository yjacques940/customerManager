using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using System;
using System.Collections.Generic;
using System.Linq;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Validators;

namespace WebApi.Services
{
    public class AppointmentService : BaseCrudService<Appointment>
    {
        public AppointmentService(IWebApiContext context) : base(context)
        {
        }

        public List<AppointmentTimeSlotInformation> GetAppointmentsByDate(DateTime dateTime)
        {
            return (
                from appointment in Context.Appointments
                join timeSlot in Context.TimeSlots on appointment.IdTimeSlot equals timeSlot.Id
                where appointment.IsActive && timeSlot.IsActive && timeSlot.StartDateTime.Date == dateTime.Date
                select new AppointmentTimeSlotInformation()
                {
                    AppointmentInfo = appointment,
                    TimeSlotInfo = timeSlot
                }).AsNoTracking().ToList();
        }

        public Appointment GetAppointmentByTimeSlot(int IdTimeSlot)
        {
            return Context.Appointments.FirstOrDefault(c => c.IdTimeSlot == IdTimeSlot);
        }

        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentsAndCustomers
            (PhoneNumberService phoneNumberService)
        {
            var appointments = (
                from appointment in Context.Appointments
                join customer in Context.Customers on appointment.IdCustomer equals customer.Id
                join timeSlot in Context.TimeSlots on appointment.IdTimeSlot equals timeSlot.Id
                where customer.IsActive && appointment.IsActive
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    TimeSlot = timeSlot,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersForCustomer(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.TimeSlot.StartDateTime).ToList();
        }

        public bool ChangeIsNewStatus(List<int> ids)
        {
            try
            {
                var appointmentsNotSeen = Context.Appointments.Where(c => c.IsNew && ids.Contains(c.Id)).ToList();
                foreach (var appointment in appointmentsNotSeen)
                {
                    appointment.IsNew = false;
                }
                Context.SaveChanges();
                return true;
            }
            catch (Exception)
            {
                return false;
            }
        }

        internal ActionResult<IEnumerable<CustomerAppointmentInformation>> GetNewAppointments(PhoneNumberService phoneNumberService)
        {
            var appointments = (
                from appointment in Context.Appointments
                join customer in Context.Customers on appointment.IdCustomer equals customer.Id
                join timeSlot in Context.TimeSlots on appointment.IdTimeSlot equals timeSlot.Id
                where customer.IsActive && appointment.IsActive && appointment.IsNew
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    TimeSlot = timeSlot,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersForCustomer(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.TimeSlot.StartDateTime).ToList();
        }

        internal List<CustomerAppointmentInformation> GetUnconfirmedAppointments(PhoneNumberService phoneNumberService)
        {
            var appointments = (
                from appointment in Context.Appointments
                join customer in Context.Customers on appointment.IdCustomer equals customer.Id
                join timeSlot in Context.TimeSlots on appointment.IdTimeSlot equals timeSlot.Id
                where customer.IsActive && appointment.IsActive && !appointment.IsConfirmed 
                    && timeSlot.StartDateTime.Date == DateConverter.CurrentEasternDateTime().Date.AddDays(1)
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    TimeSlot = timeSlot,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersForCustomer(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.TimeSlot.StartDateTime).ToList();
        }

        public List<AppointmentsDateAndTimeInformation> GetOldAppointmentsForACustomer(int userId)
        {
            var user = Context.Users.First(c => c.Id == userId);
            var appointmentsForCustomers = new List<AppointmentsDateAndTimeInformation>();
            Customer customer = Context.Customers.First(c => c.Id == user.IdCustomer);
            List<Appointment> appointments = Context.Appointments.Where(c => c.IdCustomer == customer.Id).ToList();
            foreach (var appointment in appointments)
            {
                var timeSlot = Context.TimeSlots.First(c => c.Id == appointment.IdTimeSlot);
                if (timeSlot.StartDateTime.Date <= DateConverter.CurrentEasternDateTime().Date)
                {
                    AppointmentsDateAndTimeInformation newAppointment = new AppointmentsDateAndTimeInformation
                    {
                        Appointment = appointment,
                        Date = timeSlot.StartDateTime.Date.ToString(),
                        StartTime = timeSlot.StartDateTime.TimeOfDay.ToString(),
                        EndTime = timeSlot.EndDateTime.TimeOfDay.ToString()
                    };
                    appointmentsForCustomers.Add(newAppointment);
                }
            }
            appointmentsForCustomers = appointmentsForCustomers.OrderBy(c => c.Date).OrderBy(c => c.StartTime).ToList();
            return appointmentsForCustomers;
        }

        public bool ReserveAnAppointment(AppointmentUserInformation appointmentService, IConfiguration configuration)
        {
            Appointment appointment = new Appointment();
            if (appointmentService != null)
            {
                appointment.CreatedOn = DateConverter.CurrentEasternDateTime();
                appointment.IdCustomer = (appointmentService.IdCustomer != null)
                    ? appointmentService.IdCustomer.Value
                    : Context.Users.First(c => c.Id == appointmentService.IdUser).IdCustomer;
                appointment.IdTimeSlot = appointmentService.IdTimeSlot;
                appointment.Therapist = appointmentService.Therapist;
                appointment.IsNew = true;
                appointment.IsActive = true;
                Context.Add(appointment);
                Context.SaveChanges();
                var user = GetUser(appointment.IdCustomer);
                var appointmentDate = GetAppointmentTimeSlot(appointment).StartDateTime;
                if (user != null)
                    EmailSender.SendConfirmationEmail(user.Email, appointmentDate, configuration);
                return true;
            }
            return false;
        }

        internal User GetUserFromAppointment(int appointmentId)
        {
            var appointment = Context.Appointments.Where(c => c.Id == appointmentId).First();
            var customer = Context.Customers.Where(c => c.Id == appointment.IdCustomer).First();
            return Context.Users.Where(c => c.IdCustomer == customer.Id).First();
        }

        public int CancelAppointments(List<int> appointmentsToCancel)
        {
            int tooLateToCancel = 0;
            foreach (var appointmentId in appointmentsToCancel)
            {
                var appointment = Context.Appointments.Where(c => c.Id == appointmentId).First();
                var timeSlot = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First();
                DateTime now = DateConverter.CurrentEasternDateTime();
                if(timeSlot.StartDateTime > now.AddHours(24))
                {
                    timeSlot.IsPublic = false;
                    appointment.IsActive = false;
                    Context.SaveChanges();
                }
                else
                {
                    tooLateToCancel++;
                }
            }
            return tooLateToCancel;
        }

        public List<AppointmentsDateAndTimeInformation> GetAppointmentsForCustomer(int userId)
        {
            var user = Context.Users.Where(c => c.Id == userId).First();
            List<AppointmentsDateAndTimeInformation> appointmentsForCustomer = new List<AppointmentsDateAndTimeInformation>();
            Customer customer = Context.Customers.Where(c => c.Id == user.IdCustomer).First();
            List<Appointment> appointments = Context.Appointments.Where(c => c.IdCustomer == customer.Id && c.IsActive == true).ToList();
            foreach (var appointment in appointments)
            {
                DateTime startTime = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First().StartDateTime;
                DateTime endTime = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First().EndDateTime;
                if(startTime.Date >= DateConverter.CurrentEasternDateTime().Date)
                {
                    AppointmentsDateAndTimeInformation oneAppointment = new AppointmentsDateAndTimeInformation
                    {
                        Appointment = appointment,
                        Date = startTime.Date.ToString(),
                        StartTime = startTime.TimeOfDay.ToString(),
                        EndTime = endTime.TimeOfDay.ToString()
                    };
                    appointmentsForCustomer.Add(oneAppointment);
                }
            }
            appointmentsForCustomer = appointmentsForCustomer.OrderBy(c => c.Date).OrderBy(c => c.StartTime).ToList();
            return appointmentsForCustomer;
        }

        public CustomerAppointmentInformation GetAppointmentDetails(int? userId, int appointmentId)
        {
            var customerAppointmentInformation = (
                from appointment in Context.Appointments
                join customer in Context.Customers on appointment.IdCustomer equals customer.Id
                join timeSlot in Context.TimeSlots on appointment.IdTimeSlot equals timeSlot.Id
                where appointment.Id == appointmentId
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    TimeSlot = timeSlot,
                    Appointment = appointment
                }).AsNoTracking().FirstOrDefault();

            return (userId == null)
                ? customerAppointmentInformation
                : (customerAppointmentInformation.Customer.Id == userId) ? customerAppointmentInformation : null;
        }

        internal string SendAppointmentRequest(AskForAppointmentInformation requestInfo,IConfiguration configuration)
        {
            if (requestInfo.UserId != "")
            {
                var user = Context.Users.First(c => c.Id == Convert.ToInt32(requestInfo.UserId));
                requestInfo.Email = user.Email;
                var customer = Context.Customers.First(c => c.Id == user.IdCustomer);
                requestInfo.UserName = customer.FirstName + " " + customer.LastName;
                requestInfo.PhoneNumber = Context.PhoneNumbers.First(c => c.IdCustomer == customer.Id).Phone.ToString();
            }
            EmailSender.SendAppointmentRequest(requestInfo, configuration);
            return requestInfo.UserId;
        }

        public Appointment CheckAppointmentIsAvailable(AppointmentInformation appointment)
        {
            return Context.TimeSlots.Any(c => c.Id == appointment.IdTimeSlot) ?
                    Context.Appointments.Any(c => c.IdTimeSlot == appointment.IdTimeSlot) ? null
                    : ConvertDtoToModel(appointment) : null;
        }

        public TimeSlot GetAppointmentTimeSlot(Appointment appointment)
        {
            return Context.TimeSlots.First(c => c.Id == appointment.IdTimeSlot);
        }

        private Appointment ConvertDtoToModel(AppointmentInformation appointment)
        {
            return new Appointment()
            {
                IdTimeSlot = appointment.IdTimeSlot,
                IdCustomer = appointment.IdCustomer
            };
        }

        public User GetUser(int customerId)
        {
            return Context.Users.FirstOrDefault(c => c.IdCustomer == customerId);
        }

        public AppointmentCustomerInformation GetAppointmentCustomerInformation(PhoneNumberService phoneNumberService, Appointment appointment)
        {
            var appointmentCustomer = new AppointmentCustomerInformation
            {
                Appointment = new Appointment(),
                Customer = new CustomerBasicInformation
                {
                    PhoneNumbers = new List<PhoneNumberAndTypesInformation>()
                }
            };

            Customer customer = Context.Customers.First(c => c.Id == appointment.IdCustomer);
            String customerEmail = Context.Users.FirstOrDefault(c => c.IdCustomer == customer.Id).Email;

            appointmentCustomer.Appointment = appointment;
            appointmentCustomer.Customer.Id = customer.Id;
            appointmentCustomer.Customer.Email = customerEmail ?? "";
            appointmentCustomer.Customer.FullName = $"{customer.FirstName} {customer.LastName}";
            appointmentCustomer.Customer.PhoneNumbers = phoneNumberService.GetPhoneNumbersForCustomer(customer.Id);
            return appointmentCustomer;
        }
    }
}
