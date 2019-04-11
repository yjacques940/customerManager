using System.Collections.Generic;
using System.Linq;
using WebApi.Data;
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
            List<TimeSlot> timeSlots = (from c in Context.TimeSlot where c.SlotDateTime >= date &&
                                        c.SlotDateTime < date.AddDays(1) select c).ToList();
            return timeSlots;
        }