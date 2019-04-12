using Itenso.TimePeriod;
using System;
using System.Collections.Generic;
using WebApi.Models;

namespace WebApi.Validators
{
    public class TimeSlotValidator
    {
        public static bool IsAvailable(TimeSlot newTimeSlot, List<TimeSlot> timeSlots)
        {
            if (HasATimeSlotForTheDay(timeSlots))
            {
                foreach (var appointment in timeSlots)
                {
                    if (IsOverlapping(newTimeSlot, appointment))
                    {
                        return false;
                    }
                }
            }
            return true;
        }

        private static bool IsOverlapping(TimeSlot newTimeSlot, TimeSlot timeSlots)
        {
            TimeRange newTimeSlotTime = new TimeRange(newTimeSlot.StartDateTime, newTimeSlot.EndDateTime);
            TimeRange existingTimeSlotTime = new TimeRange(timeSlots.StartDateTime, timeSlots.EndDateTime);
            return newTimeSlotTime.OverlapsWith(existingTimeSlotTime);
        }

        public static bool HasATimeSlotForTheDay(List<TimeSlot> timeSlots)
        {
            return timeSlots != null ? timeSlots.Count > 0 : false;
        }
    }
}
