using System;
using System.Collections.Generic;
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
        public static void SendEmail(string message, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage mailMessage = GetMailMessage(message);
            client.Send(mailMessage);
        }

        private static MailMessage GetMailMessage(string message)
        {
            MailMessage mailmessage = new MailMessage();
            mailmessage.IsBodyHtml = true;
            mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
            mailmessage.To.Add(new MailAddress("yannick.jacques940@gmail.com")); 
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
