using Itenso.TimePeriod;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.Validators
{
    public static class AppointmentValidator
    {
        public const int MaximumAppointmentsCount = 8;

        public static bool IsAvailable(Appointment newAppointment,List<Appointment> appointments)
        {
            if (HasReachedMaximumAppointmentsForADay(appointments))
                return false;

            if (HasAnAppointmentForTheDay(appointments))
            {
                foreach (var appointment in appointments)
                {
                    if (IsOverlapping(newAppointment, appointment))
                    {
                        return false;
                    }
                }
            }
            return true;
        }

        private static bool IsOverlapping(Appointment newAppointment, Appointment appointment)
        {
            TimeRange newOnewAppointmentTime = new TimeRange(newAppointment.AppointmentDateTime, GetEndHour(newAppointment));
            TimeRange existingAppointmentTime = new TimeRange(appointment.AppointmentDateTime, GetEndHour(appointment));
            return newOnewAppointmentTime.OverlapsWith(existingAppointmentTime);
        }

        private static DateTime GetEndHour(Appointment appointment)
        {
            return appointment.AppointmentDateTime.AddHours(appointment.DurationTime.Hour)
                .AddMinutes(appointment.DurationTime.Minute);
        }

        public static bool HasAnAppointmentForTheDay(List<Appointment> appointments)
        {
            return appointments != null ? appointments.Count > 0 : false;
        }

        public static bool HasReachedMaximumAppointmentsForADay(List<Appointment> appointments)
        {
            return appointments.Count >= MaximumAppointmentsCount;
        }
    }
}
