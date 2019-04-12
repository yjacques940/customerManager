using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using Microsoft.AspNetCore.Mvc.Formatters.Internal;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Validators;
using Microsoft.Extensions.Configuration;

namespace WebApi.Services
{
    public class AppointmentService : BaseCrudService<Appointment>
    {
        public AppointmentService(IWebApiContext context) : base(context)
        {
        }

        public ActionResult<IEnumerable<AppointmentTimeSlotInformation>> GetAppointmentsByDate(string date)
        {
            return (
                from appointment in Context.Appointments
                join timeslot in Context.TimeSlots on appointment.IdTimeSlot equals timeslot.Id
                where appointment.IsActive && timeslot.IsActive
                select new AppointmentTimeSlotInformation()
                {
                    AppointmentInfo = appointment,
                    TimeSlotInfo = timeslot
                }).AsNoTracking().ToList();
        }

        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentAndCustomers
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
                    .GetPhoneNumbersFromCustomerList(appointment.Customer.Id);
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
                    .GetPhoneNumbersFromCustomerList(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.Timeslot.StartDateTime).ToList();
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
            EmailSender.SendAppointmentRequest(requestInfo,configuration);
            return requestInfo.UserId;
        }

        public Appointment CheckAppointmentIsAvailable(AppointmentInformation appointment)
        {
            return  Context.TimeSlots.Any(c => c.Id == appointment.IdTimeSlot) ?
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

        public User GetUser(int appointmentId)
        {
            var customerId = Context.Appointments.First(c => c.Id == appointmentId).IdCustomer;
            return Context.Users.FirstOrDefault(c => c.IdCustomer == customerId);
        }
    }
}
