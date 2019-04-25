﻿using Microsoft.AspNetCore.Mvc;
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

        internal string SendAppointmentRequest(AskForAppointmentInformation requestInfo, IConfiguration configuration)
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
