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

        public ActionResult<IEnumerable<Appointment>> GetAppointmentsByDate(string date)
        {
            return Context.Appointments.Where(c => c.AppointmentDateTime.Date == Convert.ToDateTime(date).Date && c.IsActive).ToList();
        }

        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentAndCustomers
            (PhoneNumberService phoneNumberService)
        {
            var appointments = (
                from appointment in Context.Appointments
                join customer in Context.Customers on appointment.IdCustomer equals customer.Id
                where customer.IsActive && appointment.IsActive
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersFromCustomerList(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.Appointment.AppointmentDateTime).ToList();
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
                where customer.IsActive && appointment.IsActive && appointment.IsNew
                select new CustomerAppointmentInformation()
                {
                    Customer = customer,
                    Appointment = appointment
                }).AsNoTracking().ToList();

            foreach (var appointment in appointments)
            {
                appointment.PhoneNumbers = phoneNumberService
                    .GetPhoneNumbersFromCustomerList(appointment.Customer.Id);
            }
            return appointments.OrderBy(c => c.Appointment.AppointmentDateTime).ToList();
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
            var appointmentConverted = ConvertDtoToModel(appointment);
            var appointmentsForTheDay = Context.Appointments.Where(c =>
                c.IsActive && appointmentConverted.AppointmentDateTime.Date == c.AppointmentDateTime.Date).ToList();

            return AppointmentValidator.IsAvailable(appointmentConverted, appointmentsForTheDay) == false ? null : appointmentConverted;
        }

        private Appointment ConvertDtoToModel(AppointmentInformation appointment)
        {
            return new Appointment()
            {
                AppointmentDateTime = Convert.ToDateTime(appointment.AppointmentDateTime),
                DurationTime = Convert.ToDateTime(appointment.DurationTime),
                IdCustomer = int.Parse(appointment.IdCustomer)
            };
        }

        public User GetUser(int appointmentId)
        {
            var customerId = Context.Appointments.First(c => c.Id == appointmentId).IdCustomer;
            return Context.Users.FirstOrDefault(c => c.IdCustomer == customerId);
        }
    }
}
