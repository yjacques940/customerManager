using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
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

        public ActionResult<IEnumerable<Appointment>> GetAppointmentsByDate(string date)
        {
            return Context.Appointments.Where(c => c.AppointmentDateTime.Date == Convert.ToDateTime(date).Date && c.IsActive).ToList();
        }

        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentAndCustomers
            (CustomerPhoneNumberService customerPhoneNumberService)
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
                appointment.PhoneNumbers = customerPhoneNumberService
                    .GetPhoneNumbersFromCustomerList(appointment.Customer.Id);
            }
            return appointments;
        }

        public Appointment CheckAppointmentIsAvailable(Appointment appointment)
        {
            var appointmentsForTheDay = Context.Appointments.Where(c =>
                c.IsActive && appointment.AppointmentDateTime.Date == c.AppointmentDateTime.Date).ToList();

            return AppointmentValidator.IsAvailable(appointment, appointmentsForTheDay) == false ? null : appointment;
        }
    }
}
