using Microsoft.Extensions.Configuration;
using System;
using System.Linq;
using WebApi.Data;
using WebApi.Models;
using WebApi.Validators;

namespace WebApi.Services
{
    public class JobService : BaseCrudService<ActionToken>
    {
        public JobService(IWebApiContext context) : base(context)
        {
        }

        public void SendConfirmationEmailToUsers(IConfiguration configuration, AppointmentService appointmentService)
        {
            DateTime dateDelay = DateTime.Now.AddDays(1); //Change to 2 days
            var appointmentsToConfirm = appointmentService.GetAppointmentsByDate(dateDelay);
            foreach (var appointment in appointmentsToConfirm)
            {
                string userEmail = Context.Users.First(c => c.IdCustomer == appointment.AppointmentInfo.IdCustomer).Email;
                EmailSender.SendConfirmationEmail(userEmail, appointmentDate, configuration);
            }
        }
    }
}
