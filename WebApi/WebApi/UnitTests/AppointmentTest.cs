using System;
using NUnit.Framework;
using System.Collections.Generic;
using System.Linq;
using WebApi.Models;
using Itenso.TimePeriod;
using NUnit.Framework.Constraints;
using WebApi.Validators;
using UnitTests.Mocks;

namespace Tests
{
    [TestFixture]
    public class AppointmentTest
    {
        public AppointmentValidator validator { get; set; } = new AppointmentValidator();
        public Appointment newAppointment;
        public List<Appointment> Appointments;

        //OverlapsWith return true if it overlaps and false if not
        [SetUp]
        public void SetUpTest()
        {
            newAppointment = new AppointmentMock().GetNewAppointment();
            Appointments = new AppointmentMock().GetAppointmentList(3);
        }

        [Test]
        public void IsNotOverlapping()
        {
            newAppointment.Time = Convert.ToDateTime("11:00");
            Assert.AreEqual(true, validator.IsAvailable(newAppointment,Appointments));
        }

        [Test] //#1
        public void CanCreateAppointmentWhileThereIsNoAppointmentForTheDay()
        {
            Assert.AreEqual(true, validator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(0)));
        }

        [Test] //#2
        public void IsOverlapping()
        {
            newAppointment.Time = Convert.ToDateTime("12:00");
            var appointments = new AppointmentMock().GetAppointmentList(1, new List<string> { "12:00" });
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#4
        public void CreateAnAppointmentBetweenTwoAppointments()
        {
            newAppointment.Time = Convert.ToDateTime("9:00");
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "8:00","11:00" });
            Assert.AreEqual(true, validator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#5
        public void CreateAnAppointmentBetweenTwoAppointmentsThatFinishInNextAppointment()
        {
            newAppointment.Time = Convert.ToDateTime("10:00");
            newAppointment.DurationTime = new TimeSpan(0, 1, 30, 0);
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "9:00", "11:00" });
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#6
        public void CreateAnAppointmentThatStartInAnotherOneAndFinishBeforeNextAppointment()
        {
            newAppointment.Time = Convert.ToDateTime("9:30");
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "9:00", "11:00" });
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#7
        public void CreateAnAppointment()
        {
            newAppointment.Time = Convert.ToDateTime("9:30");
            newAppointment.DurationTime = new TimeSpan(0, 1, 30, 0);
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "9:00", "10:30" });
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#8
        public void MaximumAppointmentsForADayHaveBeenReached()
        {
            Assert.AreEqual(true, validator.HasReachedMaximumAppointmentsForADay(new AppointmentMock().GetAppointmentList(8)));
        }

        [Test] //#9
        public void CanCreateMaximumAppointmentForTheDay()
        {
            newAppointment.Time = Convert.ToDateTime("17:00");
            Assert.AreEqual(true,validator.IsAvailable(newAppointment,new AppointmentMock().GetAppointmentList(7)));
        }

        [Test] //#10
        public void MaximumAppointmentsForADayHaveNotBeenReached()
        {
            Assert.AreEqual(false, validator.HasReachedMaximumAppointmentsForADay(Appointments));
        }

        [Test] //#11
        public void CanCreateAppointmentThatStartAndFinishBeforeAnotherOne()
        {
            newAppointment.Time = Convert.ToDateTime("8:00");
            Assert.AreEqual(true, validator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1,new List<string> {"9:15"})));
        }

        [Test] //#12
        public void CreateAnAppointmentThatFinishInAnotherOne()
        {
            newAppointment.Time = Convert.ToDateTime("8:00");
            newAppointment.DurationTime = new TimeSpan(0, 1, 30, 0);
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "9:15" })));
        }

        [Test] //#13
        public void CreateAnAppointmentThatStartInAnotherOne()
        {
            newAppointment.Time = Convert.ToDateTime("9:45");
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "9:15" })));
        }

        [Test] //#14
        public void CreateAnAppointmentThatStartAfterAnotherOne()
        {
            newAppointment.Time = Convert.ToDateTime("14:45");
            Assert.AreEqual(true, validator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "13:00" })));
        }

        [Test] //#15
        public void CreateAnAppointmentThatStartBeforeAndFinishAfterAnotherOne()
        {
            newAppointment.Time = Convert.ToDateTime("12:45");
            newAppointment.DurationTime = new TimeSpan(0,1,30,0);
            Assert.AreEqual(false, validator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "13:00" })));
        }

        [Test]
        public void HasAtLeastOneAppointmentForTheDay()
        {
            Assert.AreEqual(true, validator.HasAnAppointmentForTheDay(new AppointmentMock().GetAppointmentList(4)));
        }

        [Test]
        public void DontHaveAnyAppointmentForTheDay()
        {
            Assert.AreEqual(false, validator.HasAnAppointmentForTheDay(new AppointmentMock().GetAppointmentList(0)));
        }
    }
}