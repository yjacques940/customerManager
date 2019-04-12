using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Mail;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using WebApi.DTO;

namespace WebApi.Validators
{
    public static class EmailSender
    {
        public static bool SendEmail(string message, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage mailMessage = GetMailMessage(message);
            
            try
            {
                client.Send(mailMessage);
                return true;
            }
            catch
            {
                return false;
            }
        }

        public static bool SendNewAppointmentsToEmployees(ActionResult<IEnumerable<CustomerAppointmentInformation>> appointments, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage mailMessage = GetNewAppointmentMailMessage(appointments);

            try
            {
                client.Send(mailMessage);
                return true;
            }
            catch
            {
                return false;
            }
        }

        private static MailMessage GetNewAppointmentMailMessage(ActionResult<IEnumerable<CustomerAppointmentInformation>> appointments)
        {
            
            string newHtml = "";
            MailMessage mailmessage = new MailMessage();
            mailmessage.IsBodyHtml = true;
            mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
            mailmessage.To.Add(new MailAddress("exeinformatiquedev@gmail.com"));
            mailmessage.Subject = "Nouveaux rendez-vous de la journée";
            string htmlWithNewAppointments = GetHtmlWithNewAppointments(appointments);
            using (StreamReader reader = File.OpenText("EmailTemplate/newAppointments.html"))
            {
                var htmlFile = reader.ReadToEnd();
                newHtml = htmlFile.Replace("[NewAppointmentsy]", htmlWithNewAppointments);
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }

        private static string GetHtmlWithNewAppointments(ActionResult<IEnumerable<CustomerAppointmentInformation>> appointments)
        {
            List<CustomerAppointmentInformation> newAppointments = appointments.Value.ToList();
            string html = "";
            foreach (var appointment in newAppointments)
            {
                html +=
                    $"<div class=\"moreInfoBorder\" style=\"padding:10px\">" +
                    $"<div>Nom du client: {appointment.Customer.FirstName} {appointment.Customer.LastName}</div>" +
                    $"<div>Rendez-vous : {appointment.Timeslot.StartDateTime.Date.ToShortDateString()} à " +
                    $"{appointment.Timeslot.StartDateTime.ToString("HH:mm")}</div>" +
                    $"</div></br>";
            }
            return html;
        }

        public static bool SendConfirmationEmail(string userEmail,DateTime appointment, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage MailMessageForConfirmAppointment = GetMailMessageToConfirmAppointment(userEmail, appointment);

            try
            {
                client.Send(MailMessageForConfirmAppointment);
                return true;
            }
            catch
            {
                return false;
            }
        }

        private static MailMessage GetMailMessageToConfirmAppointment(string emailTo, DateTime AppointmentDateTime)
        {
            using (StreamReader reader = File.OpenText("EmailTemplate/index.html"))
            {
                string newHtml = "";
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress(emailTo));
                mailmessage.Subject = "Confirmation de rendez-vous";

                var htmlFile = reader.ReadToEnd();
                newHtml = htmlFile.Replace("[DateOfTheDayx]", DateTime.Now.Date.ToString("yyyy/MM/dd"));
                newHtml = newHtml.Replace("[TimeOfTheDayx]", DateTime.Now.ToShortTimeString().ToString());
                newHtml = newHtml.Replace("[AppointmentHourx]", AppointmentDateTime.ToShortTimeString());
                newHtml = newHtml.Replace("[AppointmentDatex]", AppointmentDateTime.Date.ToString("yyyy/MM/dd"));
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }

        private static MailMessage GetMailMessage(string message)
        {
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress("exeinformatiquedev@gmail.com"));
                mailmessage.Subject = "A user reported a bug";
                mailmessage.Body = message;
                return mailmessage;
        }

        private static SmtpClient GetSmtpClient(IConfiguration configuration)
        {
            SmtpClient client = new SmtpClient();
            client.Port = 25;
            client.Host = "smtp.gmail.com";
            client.EnableSsl = true;
            client.Timeout = 10000;
            client.DeliveryMethod = SmtpDeliveryMethod.Network;
            client.UseDefaultCredentials = false;
            client.Credentials =
                new NetworkCredential(configuration.GetSection("EmailAddress").Value,configuration.GetSection("EmailPassword").Value);
            return client;
        }

        internal static void SendAppointmentRequest(AskForAppointmentInformation requestInfo, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage mailMessage = GetMailMessageForAppointmentRequest(requestInfo);
            client.Send(mailMessage);
        }

        private static MailMessage GetMailMessageForAppointmentRequest(AskForAppointmentInformation requestInfo)
        {
            using (StreamReader reader = File.OpenText("EmailTemplate/askForAppointment.html"))
            {
                string newHtml = "";
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress("exeinformatiquedev@gmail.com"));
                mailmessage.Subject = "Un client vous a envoyé une demande de rendez-vous";
                var htmlFile     = reader.ReadToEnd();
                newHtml          = htmlFile.Replace("[AppointmentTimeOfDayx]", requestInfo.TimeOfDay);
                newHtml          = newHtml.Replace("[AppointmentDatex]", requestInfo.Date);
                newHtml          = newHtml.Replace("[SenderEmailx]", requestInfo.Email);
                newHtml          = newHtml.Replace("[OtherInformationx]",  requestInfo.MoreInformation != "" ? "<div>Informations supplémentaires  :</div><div class=\"moreInfoBorder\">" + requestInfo.MoreInformation + "</div>": "");
                newHtml          = newHtml.Replace("[SenderPhoneNumberx]", requestInfo.PhoneNumber);
                newHtml          = newHtml.Replace("[SenderNamex]", requestInfo.UserName);
                newHtml          = newHtml.Replace("[AppointmentTypex]", requestInfo.TypeOfTreatment);
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }
    }
}
