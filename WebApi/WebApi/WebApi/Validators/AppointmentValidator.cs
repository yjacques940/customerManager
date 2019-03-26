using Itenso.TimePeriod;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.Validators
{
    public class AppointmentValidator
    {
        public const int MaximumAppointmentsCount = 8;

        public bool IsAvailable(Appointment newAppointment,List<Appointment> appointments)
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

        private bool IsOverlapping(Appointment newAppointment, Appointment appointment)
        {
            TimeRange newOne = new TimeRange(newAppointment.Time, newAppointment.Time.AddHours(newAppointment.DurationTime.TotalHours));
            TimeRange existingAppointment = new TimeRange(appointment.Time, appointment.Time.AddHours(appointment.DurationTime.TotalHours));
            return newOne.OverlapsWith(existingAppointment);
        }

        public bool HasAnAppointmentForTheDay(List<Appointment> appointments)
        {
            return appointments.Count > 0 && appointments != null;
        }

        public bool HasReachedMaximumAppointmentsForADay(List<Appointment> appointments)
        {
            return appointments.Count >= MaximumAppointmentsCount;
        }
    }
}
