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
    }
}
