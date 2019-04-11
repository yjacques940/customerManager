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
            TimeRange newTimeSlotTime = new TimeRange(newTimeSlot.SlotDateTime, GetEndHour(newTimeSlot));
            TimeRange existingTimeSlotTime = new TimeRange(timeSlots.SlotDateTime, GetEndHour(timeSlots));
            return newTimeSlotTime.OverlapsWith(existingTimeSlotTime);
        }

        private static DateTime GetEndHour(TimeSlot timeSlot)
        {
            return timeSlot.SlotDateTime.AddHours(timeSlot.DurationTime.Hour)
                .AddMinutes(timeSlot.DurationTime.Minute);
        }

        public static bool HasATimeSlotForTheDay(List<TimeSlot> timeSlots)
        {
            return timeSlots != null ? timeSlots.Count > 0 : false;
        }
    }
}
