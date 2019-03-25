using System;
using NUnit.Framework;
using System.Collections.Generic;
using System.Linq;
using WebApi.Models;

namespace Tests
{
    [TestFixture]
    public class Tests
    {
        [TestCase()]
        public bool IsOverlapping(Appointment newAppointment)
        {
            var test = new List<Appointment>
            {
                new Appointment()
                {
                    Id = 35136515,
                    IsActive = true,
                    Date = DateTime.Now.Date,
                    Time = Convert.ToDateTime("12:00"),
                    DurationTime = new TimeSpan(0, 1, 0, 0)
                }
            };

            var app = new Appointment()
            {
                Id = 351658156,
                IsActive = true,
                Date = DateTime.Now.Date,
                Time = Convert.ToDateTime("12:30"),
                DurationTime = new TimeSpan(0, 1, 0, 0)
            };

            var x = test.Any(c => c.Date == app.Date && c.Time < app.Time && app.Time < c.Time + c.DurationTime);
            Assert.AreEqual(0, x == true ? 1 : 0);
            return false;
        }
    }
}