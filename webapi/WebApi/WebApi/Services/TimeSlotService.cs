using System;
using System.Collections.Generic;
using System.Linq;
using Microsoft.EntityFrameworkCore;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Validators;

namespace WebApi.Services
{
    public class TimeSlotService : BaseCrudService<TimeSlot>
    {
        public TimeSlotService(IWebApiContext context) : base(context)
        {
        }

        public bool CreateNewTimeSlot(TimeSlot timeSlot)
        {
            if (timeSlot != null)
            {
                timeSlot.IsActive = true;
                if (!timeSlot.IsAvailable) {
                    timeSlot.IsPublic = false;
                }
                Context.Add(timeSlot);
                Context.SaveChanges();
                return true;
            }
            return false;
        }

        public TimeSlot GetByAppointment(int idAppointment)
        {
            return Context.TimeSlots.FirstOrDefault(t => t.Id ==
                Context.Appointments.FirstOrDefault(a => a.Id == idAppointment).IdTimeSlot);
        }

        public bool IsAvailable(TimeSlot newTimeSlot)
        {
            List<TimeSlot> timeSlots = Context.TimeSlots
                .Where(c => c.IsActive && (c.StartDateTime.Date == newTimeSlot.StartDateTime.Date)).ToList();
            return TimeSlotValidator.IsAvailable(newTimeSlot, timeSlots);
        }
   
        public List<TimeSlot> GetTimeSlotForTheDay(DateTime date)
        {
            List<TimeSlot> timeSlots = Context.TimeSlots.Where(c => c.StartDateTime.Date == date.Date && c.IsActive && c.IsPublic).ToList();
            List<TimeSlot> timeSlotsToReturn = new List<TimeSlot>();
            timeSlotsToReturn.AddRange(timeSlots);
            List<Appointment> appointments = Context.Appointments.ToList();
            foreach (var timeSlot in timeSlots)
            {
                foreach (var appointment in appointments)
                {
                    if(appointment.IdTimeSlot == timeSlot.Id)
                    {
                        timeSlotsToReturn.Remove(timeSlot);
                    }
                }
            }
            return timeSlotsToReturn;
        }

        public List<TimeSlot> GetFreeTimeSlots()
        {
            List<TimeSlot> timeSlots = Context.TimeSlots.Where(c => c.StartDateTime > DateTime.Now && c.IsAvailable && c.IsPublic).ToList();
            List<Appointment> appointments = Context.Appointments.ToList();
            List<TimeSlot> timeSlotsToReturn = new List<TimeSlot>();
            timeSlotsToReturn.AddRange(timeSlots);
            foreach (var timeSlot in timeSlots)
            {
                foreach (var appointment in appointments)
                {
                    if (appointment.IdTimeSlot == timeSlot.Id)
                    {
                        timeSlotsToReturn.Remove(timeSlot);
                    }
                }
            }
            return timeSlotsToReturn;
        }

        public bool CheckTimeSlotAvailable(int id)
        {
            return !Context.Appointments.Any(c => c.IdTimeSlot == id);
        }

        public List<BasicTimeSlotAppointmentCustomerInformation> GetBasicTimeSlotAppointmentCustomerInfo(
                PhoneNumberService phoneNumberService)
        {
            var basicTimeSlotAppointmentCustomerInformationList =
                    new List<BasicTimeSlotAppointmentCustomerInformation>();

            var timeSlots = Context.TimeSlots.Where(c => c.IsActive).ToList();
            foreach (var timeSlot in timeSlots)
            {
                var appointment = Context.Appointments.FirstOrDefault(c => c.IsActive && c.IdTimeSlot == timeSlot.Id);
                if (appointment != null)
                {
                    var basicTimeSlotAppointmentCustomerInformation =
                            new BasicTimeSlotAppointmentCustomerInformation();

                    basicTimeSlotAppointmentCustomerInformation.CustomerInfo =
                            new CustomerBasicInformation();

                    basicTimeSlotAppointmentCustomerInformation.CustomerInfo.PhoneNumbers =
                            new List<PhoneNumberAndTypesInformation>();

                    basicTimeSlotAppointmentCustomerInformation.IdTimeSlot = timeSlot.Id;
                    basicTimeSlotAppointmentCustomerInformation.IdAppointment = appointment.Id;
                    basicTimeSlotAppointmentCustomerInformation.NotesTimeSlot = timeSlot.Notes;

                    var customer = Context.Customers.First(c => c.Id == appointment.IdCustomer);

                    basicTimeSlotAppointmentCustomerInformation.CustomerInfo.Id = customer.Id;

                    basicTimeSlotAppointmentCustomerInformation.CustomerInfo.Email =
                            Context.Users.FirstOrDefault(c => c.IdCustomer == customer.Id).Email;

                    basicTimeSlotAppointmentCustomerInformation.CustomerInfo.FullName =
                            $"{customer.FirstName} {customer.LastName}";

                    basicTimeSlotAppointmentCustomerInformation.CustomerInfo.PhoneNumbers =
                            phoneNumberService.GetPhoneNumbersForCustomer(customer.Id);

                    basicTimeSlotAppointmentCustomerInformationList.Add(basicTimeSlotAppointmentCustomerInformation);
                }
            }
            return basicTimeSlotAppointmentCustomerInformationList;
        }
    }
}
