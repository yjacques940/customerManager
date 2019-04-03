using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Mail;
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

        private static MailMessage GetMailMessage(string message)
        {
            using (StreamReader reader = File.OpenText("../WebApi/markup/index.html"))
            {
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress("exeinformatiquedev@gmail.com"));
                mailmessage.Subject = "A user reported a bug";
                // mailmessage.Body = message;
                mailmessage.Body = reader.ReadToEnd();
                return mailmessage;
            }
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
