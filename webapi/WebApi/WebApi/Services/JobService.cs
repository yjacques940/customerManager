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

        public void SendAskConfirmationEmailToUsers(IConfiguration config, AppointmentService appointmentService)
        {
            DateTime dateDelay = DateTime.Now.AddDays(2); 
            var appointmentsToConfirm = appointmentService.GetAppointmentsByDate(dateDelay);
            foreach (var info in appointmentsToConfirm)
            {
                ActionToken actionToken = new ActionToken
                {
                    IsActive = true,
                    Action = "ConfirmAppointment",
                    CreatedOn = DateTime.Now,
                    ExpirationDate = DateTime.Now.AddDays(2),
                    IdAppointment = info.AppointmentInfo.Id,
                    IdUser = info.AppointmentInfo.IdCustomer,
                    Token = Guid.NewGuid().ToString()
                };
                Context.ActionTokens.Add(actionToken);
                Context.SaveChanges();

                Customer customer = Context.Customers.First(c => c.Id == info.AppointmentInfo.IdCustomer);
                string userEmail = Context.Users.First(c => c.IdCustomer == info.AppointmentInfo.IdCustomer).Email;
                EmailSender.SendAskConfirmationToUserEmail(
                    $"{customer.FirstName} {customer.LastName}",
                    userEmail,
                    actionToken.Token,
                    info.TimeSlotInfo.StartDateTime,
                    config
                );
            }
        }

        public void SendUnconfirmedAppointmentsToEmployees
            (IConfiguration config, AppointmentService appointmentService, PhoneNumberService phoneNumberService)
        {
            DateTime dateDelay = DateTime.Now.AddDays(1);
            var unconfirmedAppointment = appointmentService.GetUnconfirmedAppointments(phoneNumberService);
            if (unconfirmedAppointment.Count() > 0)
            {
                EmailSender.SendUnconfirmedAppointmentsToEmployees(unconfirmedAppointment, config);
            }
        }
    }
}
