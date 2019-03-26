using System;
using System.Collections.Generic;
using System.Text;
using WebApi.Models;

namespace UnitTests.Mocks
{
    public class AppointmentMock
    {
        public Appointment GetNewAppointment()
        {
           return new Appointment()
            {
                Id = 351658156,
                IsActive = true,
                Date = DateTime.Now.Date,
                Time = Convert.ToDateTime("12:30"),
                DurationTime = new TimeSpan(0, 1, 0, 0)
            };
        }

        public List<Appointment> GetAppointmentList(int numberOfAppointments,List<string> hours = null)
         {
            var appointments = new List<Appointment>();
            if (numberOfAppointments == 0)
                return appointments;

            for (int i = 0; i < numberOfAppointments; i++)
            {
                appointments.Add(
                    new Appointment()
                    {
                        Id = i,
                        IsActive = true,
                        Date = DateTime.Now.Date,
                        Time = Convert.ToDateTime(hours != null ? hours[i] : $"{8 + i}:00"),
                        DurationTime = new TimeSpan(0, 1, 0, 0)
                    });
            }
            return appointments;
        }
    }
}
