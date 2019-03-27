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
        public Appointment newAppointment;
        public List<Appointment> Appointments;
        
        [SetUp]
        public void SetUpTest()
        {
            newAppointment = new AppointmentMock().GetNewAppointment();
            Appointments = new AppointmentMock().GetAppointmentList(3);
        }

        [Test]
        public void IsNotOverlapping()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("11:00");
            Assert.AreEqual(true, AppointmentValidator.IsAvailable(newAppointment,Appointments));
        }

        [Test] //#1
        public void CanCreateAppointmentWhileThereIsNoAppointmentForTheDay()
        {
            Assert.AreEqual(true, AppointmentValidator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(0)));
        }

        [Test] //#2
        public void IsOverlapping()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("12:00");
            var appointments = new AppointmentMock().GetAppointmentList(1, new List<string> { "12:00" });
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#4
        public void CreateAnAppointmentBetweenTwoAppointments()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("9:00");
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "8:00","11:00" });
            Assert.AreEqual(true, AppointmentValidator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#5
        public void CreateAnAppointmentBetweenTwoAppointmentsThatEndsInNextAppointment()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("10:00");
            newAppointment.DurationTime = new DateTime().AddHours(1).AddMinutes(30);
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "9:00", "11:00" });
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#6
        public void CreateAnAppointmentThatStartInAnotherOneAndEndsBeforeNextAppointment()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("9:30");
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "9:00", "11:00" });
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#7
        public void CreateAnAppointment()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("9:30");
            newAppointment.DurationTime = new DateTime().AddHours(1).AddMinutes(30);
            var appointments = new AppointmentMock().GetAppointmentList(2, new List<string> { "9:00", "10:30" });
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, appointments));
        }

        [Test] //#8
        public void MaximumAppointmentsForADayHaveBeenReached()
        {
            Assert.AreEqual(true, AppointmentValidator.HasReachedMaximumAppointmentsForADay(new AppointmentMock().GetAppointmentList(8)));
        }

        [Test] //#9
        public void CanCreateLastAppointmentForTheDay()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("17:00");
            Assert.AreEqual(true, AppointmentValidator.IsAvailable(newAppointment,new AppointmentMock().GetAppointmentList(7)));
        }

        [Test] //#10
        public void MaximumAppointmentsForADayHaveNotBeenReached()
        {
            Assert.AreEqual(false, AppointmentValidator.HasReachedMaximumAppointmentsForADay(Appointments));
        }

        [Test] //#11
        public void CanCreateAppointmentThatStartAndEndsBeforeAnotherOne()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("8:00");
            Assert.AreEqual(true, AppointmentValidator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1,new List<string> {"9:15"})));
        }

        [Test] //#12
        public void CreateAnAppointmentThatEndsInAnotherOne()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("8:00");
            newAppointment.DurationTime = new DateTime().AddHours(1).AddMinutes(30);
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "9:15" })));
        }

        [Test] //#13
        public void CreateAnAppointmentThatStartsInAnotherOne()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("9:45");
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "9:15" })));
        }

        [Test] //#14
        public void CreateAnAppointmentThatStartsAfterAnotherOne()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("14:45");
            Assert.AreEqual(true, AppointmentValidator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "13:00" })));
        }

        [Test] //#15
        public void CreateAnAppointmentThatStartsBeforeAndFinishAfterAnotherOne()
        {
            newAppointment.AppointmentDateTime = Convert.ToDateTime("12:45");
            newAppointment.DurationTime = new DateTime().AddHours(1).AddMinutes(30);
            Assert.AreEqual(false, AppointmentValidator.IsAvailable(newAppointment, new AppointmentMock().GetAppointmentList(1, new List<string> { "13:00" })));
        }

        [Test]
        public void HasAtLeastOneAppointmentForTheDay()
        {
            Assert.AreEqual(true, AppointmentValidator.HasAnAppointmentForTheDay(new AppointmentMock().GetAppointmentList(4)));
        }

        [Test]
        public void DontHaveAnyAppointmentForTheDay()
        {
            Assert.AreEqual(false, AppointmentValidator.HasAnAppointmentForTheDay(new AppointmentMock().GetAppointmentList(0)));
        }
    }
}