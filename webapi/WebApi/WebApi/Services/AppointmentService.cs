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
                join timeslot in Context.TimeSlots on appointment.IdTimeSlot equals timeslot.Id
                where appointment.IsActive && timeslot.IsActive && timeslot.StartDateTime.Date == dateTime.Date
                select new AppointmentTimeSlotInformation()
                {
                    AppointmentInfo = appointment,
                    TimeSlotInfo = timeslot
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
                join timeslot in Context.TimeSlots on appointment.IdTimeSlot equals timeslot.Id
                where customer.IsActive && appointment.IsActive
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    Timeslot = timeslot,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersForCustomer(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.Timeslot.StartDateTime).ToList();
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
                join timeslot in Context.TimeSlots on appointment.IdTimeSlot equals timeslot.Id
                where customer.IsActive && appointment.IsActive && appointment.IsNew
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    Timeslot = timeslot,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersForCustomer(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.Timeslot.StartDateTime).ToList();
        }

        public bool ReserveAnAppointment(AppointmentUserInformation appointmentService, IConfiguration configuration)
        {
            Appointment appointment = new Appointment();
            if (appointmentService != null)
            {
                appointment.CreatedOn = DateTime.Now;
                appointment.IdCustomer = Context.Users.First(c => c.Id == appointmentService.IdUser).IdCustomer;
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
                DateTime now = DateTime.Now;
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
            List<AppointmentsDateAndTimeInformation> appointmentsForCustomers = new List<AppointmentsDateAndTimeInformation>();
            Customer customer = Context.Customers.Where(c => c.Id == user.IdCustomer).First();
            List<Appointment> appointments = Context.Appointments.Where(c => c.IdCustomer == customer.Id && c.IsActive == true).ToList();
            foreach (var appointment in appointments)
            {
                DateTime startTime = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First().StartDateTime;
                DateTime endTime = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First().EndDateTime;
                if(startTime > DateTime.Now)
                {
                    AppointmentsDateAndTimeInformation oneAppointment = new AppointmentsDateAndTimeInformation();
                    oneAppointment.appointment = appointment;
                    oneAppointment.Date = startTime.Date.ToString();
                    oneAppointment.StartTime = startTime.TimeOfDay.ToString();
                    oneAppointment.EndTime = endTime.TimeOfDay.ToString();
                    appointmentsForCustomers.Add(oneAppointment);
                }
            }
            appointmentsForCustomers = appointmentsForCustomers.OrderBy(c => c.Date).OrderBy(c => c.StartTime).ToList();
            return appointmentsForCustomers;
        }

        internal object GetAppointmentsForCustomer(int userid)
        {
            var user = Context.Users.Where(c => c.Id == userId).First();
            List<AppointmentsForCustomer> appointmentsForCustomers = new List<AppointmentsForCustomer>();
            Customer customer = Context.Customers.Where(c => c.Id == user.IdCustomer).First();
            List<Appointment> appointments = Context.Appointments.Where(c => c.IdCustomer == customer.Id && c.IsActive == true).ToList();
            foreach (var appointment in appointments)
            {
                DateTime startTime = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First().StartDateTime;
                DateTime endTime = Context.TimeSlots.Where(c => c.Id == appointment.IdTimeSlot).First().EndDateTime;
                AppointmentsForCustomer oneAppointment = new AppointmentsForCustomer();
                oneAppointment.appointment = appointment;
                oneAppointment.Date = startTime.Date.ToString();
                oneAppointment.StartTime = startTime.TimeOfDay.ToString();
                oneAppointment.EndTime = endTime.TimeOfDay.ToString();
                appointmentsForCustomers.Add(oneAppointment);
            }
            appointmentsForCustomers = appointmentsForCustomers.OrderBy(c => c.Date).OrderBy(c => c.StartTime).ToList();
            return appointmentsForCustomers;
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
            var appointmentCustomer = new AppointmentCustomerInformation();
            appointmentCustomer.Appointment = new Appointment();
            appointmentCustomer.Customer = new CustomerBasicInformation();
            appointmentCustomer.Customer.phoneNumbers = new List<PhoneNumberAndTypesInformation>();

            Customer customer = Context.Customers.First(c => c.Id == appointment.IdCustomer);
            String customerEmail = Context.Users.FirstOrDefault(c => c.IdCustomer == customer.Id).Email;

            appointmentCustomer.Appointment = appointment;
            appointmentCustomer.Customer.Id = customer.Id;
            appointmentCustomer.Customer.Email = (customerEmail != null) ? customerEmail : "";
            appointmentCustomer.Customer.FullName = $"{customer.FirstName} {customer.LastName}";
            appointmentCustomer.Customer.phoneNumbers = phoneNumberService.GetPhoneNumbersForCustomer(customer.Id);
            return appointmentCustomer;
        }
    }
}
