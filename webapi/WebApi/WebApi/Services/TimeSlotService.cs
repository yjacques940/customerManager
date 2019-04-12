using System.Collections.Generic;
using System.Linq;
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

        public bool IsAvailable(TimeSlot newTimeSlot)
        {
            List<TimeSlot> timeslots = Context.TimeSlots
                .Where(c => c.StartDateTime.Date == newTimeSlot.StartDateTime.Date).ToList();
            return TimeSlotValidator.IsAvailable(newTimeSlot, timeslots);
        }
    }
}

        public List<TimeSlot> GetTimeSlotForTheDay(DateTime date)
        {
            List<TimeSlot> timeSlots = Context.TimeSlot.Where(c => c.SlotDateTime.Date == date.Date && c.IsActive && c.IsPublic).ToList();
            List<TimeSlot> timeSlotsToReturn = new List<TimeSlot>();
            timeSlotsToReturn.AddRange(timeSlots);
            List<Appointment> appointments = Context.Appointments.Where(c => c.AppointmentDateTime.Date == date.Date).ToList();
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
            List<TimeSlot> timeSlots = Context.TimeSlot.Where(c => c.SlotDateTime > DateTime.Now).ToList();
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
    }
}
