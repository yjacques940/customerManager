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

        public static bool SendConfirmationEmail(string userEmail, DateTime AppointmentDateTime, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage MailMessageForConfirmAppointment = GetMailMessageToConfirmAppointment(userEmail, AppointmentDateTime);

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

        public static bool SendAskConfirmationToUserEmail(string customerName, string userEmail, string token, DateTime AppointmentDateTime, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage MailMessageAskConfirmationToUser = GetMailMessageToAskConfirmationToUser(customerName, userEmail, token, AppointmentDateTime);

            try
            {
                client.Send(MailMessageAskConfirmationToUser);
                return true;
            }
            catch
            {
                return false;
            }
        }
        public static bool SendEmailToChangePassword(string userEmail, string token,  IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage MailMessageToChangePassword = GetMailMessageToChangePassword(userEmail, token);

            try
            {
                client.Send(MailMessageToChangePassword);
                return true;
            }
            catch
            {
                return false;
            }
        }


        public static bool SendUnconfirmedAppointmentsToEmployees(ActionResult<IEnumerable<CustomerAppointmentInformation>> appointments, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage mailMessage = GetUnconfirmedAppointmentMailMessage(appointments);

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

        private static MailMessage GetUnconfirmedAppointmentMailMessage(ActionResult<IEnumerable<CustomerAppointmentInformation>> appointments)
        {
            string newHtml = "";
            MailMessage mailmessage = new MailMessage();
            mailmessage.IsBodyHtml = true;
            mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
            mailmessage.To.Add(new MailAddress("exeinformatiquedev@gmail.com"));
            mailmessage.Subject = "Rendez-vous non-confirmés pour le " + DateTime.Now.AddDays(1).ToString("dd/MM/yyyy");
            string htmlWithNewAppointments = GetHtmlWithUnconfirmedAppointments(appointments);
            using (StreamReader reader = File.OpenText("EmailTemplate/unconfirmedAppointments.html"))
            {
                var htmlFile = reader.ReadToEnd();
                newHtml = htmlFile.Replace("[NewAppointmentsy]", htmlWithNewAppointments);
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }

        private static string GetHtmlWithUnconfirmedAppointments(ActionResult<IEnumerable<CustomerAppointmentInformation>> appointments)
        {
            List<CustomerAppointmentInformation> unconfirmedAppointments = appointments.Value.ToList();
            string html = "";
            foreach (var appointment in unconfirmedAppointments)
            {
                html +=
                    $"<div class=\"moreInfoBorder\" style=\"padding:10px\">" +
                    $"<div>Nom du client: {appointment.Customer.FirstName} {appointment.Customer.LastName}</div>" +
                    $"<div>Rendez-vous : {appointment.Timeslot.StartDateTime.Date.ToShortDateString()} à " +
                    $"{appointment.Timeslot.StartDateTime.ToString("HH:mm")}</div>" +
                    $"</div><div>Numéros de Téléphones:";
                foreach (var phoneNumber in appointment.PhoneNumbers)
                {
                    html += $"<div>{phoneNumber.PhoneType}: {phoneNumber.Phone}</div>";
                }
                html += $"</div></br>";
            }
            return html;
        }

        private static MailMessage GetMailMessageToAskConfirmationToUser(string customerName, string emailTo, string token, DateTime AppointmentDateTime)
        {
            using (StreamReader reader = File.OpenText("EmailTemplate/askConfirmationToUser.html"))
            {
                string newHtml = "";
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress(emailTo));
                mailmessage.Subject = "Rendez-vous à confirmer pour le " + AppointmentDateTime.ToString("dd/MM/yyyy");

                var htmlFile = reader.ReadToEnd();
                newHtml = htmlFile.Replace("[ActionTokenx]", token);
                newHtml = newHtml.Replace("[AppointmentHourx]", AppointmentDateTime.ToShortTimeString());
                newHtml = newHtml.Replace("[AppointmentDatex]", AppointmentDateTime.ToString("dd/MM/yyyy"));
                newHtml = newHtml.Replace("[CustomerNamex]", customerName);
                newHtml = newHtml.Replace("[ServerURLx]", "http://localhost");
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }

        private static MailMessage GetMailMessageToChangePassword(string emailTo, string token)
        {
            using (StreamReader reader = File.OpenText("EmailTemplate/changePasswordWithToken.html"))
            {
                string newHtml = "";
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress(emailTo));
                mailmessage.Subject = "Demande de changement de mot de passe";

                var htmlFile = reader.ReadToEnd();
                newHtml = htmlFile.Replace("[ActionTokenx]", token);
                newHtml = newHtml.Replace("[ServerURLx]", "http://localhost");
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }

        public static bool SendCancellationEmail(string userEmail, int tooLateToCancel, int appointmentCount, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage MailMessageForCancellationAppointment = GetMailMessageToCancelAppointment(userEmail, tooLateToCancel, appointmentCount);

            try
            {
                client.Send(MailMessageForCancellationAppointment);
                return true;
            }
            catch
            {
                return false;
            }
        }

        private static MailMessage GetMailMessageToCancelAppointment(string userEmail, int tooLateToCancel, int appointmentCount)
        {
            using (StreamReader reader = File.OpenText("EmailTemplate/cancelAppointment.html"))
            {
                string newHtml = "";
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress(userEmail));
                mailmessage.Subject = "Confirmation d'annulation";
                var htmlFile = reader.ReadToEnd();
                if (appointmentCount > 1)
                {
                    newHtml = htmlFile.Replace("[AppointmentAmount]", "Vos rendez-vous ont étés annulés avec succès.");
                }
                else
                {
                    newHtml = htmlFile.Replace("[AppointmentAmount]", "Votre rendez-vous à été annulé avec succès.");
                }
                if(tooLateToCancel > 0)
                {
                    newHtml = newHtml.Replace("[TooLateToCancel]", "Cependant, au moins un rendez-vous est dans moins de 24 heures et n'a pu être annulé." +
                        " Veuillez nous appeler au (418) 774-0246 pour l'annulation de ce dernier.");
                }
                else
                {
                    newHtml = newHtml.Replace("[TooLateToCancel]", "");
                }
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }

        public static bool SendCancellationEmail(string userEmail, bool tooLateToCancel, int appointmentCount, IConfiguration configuration)
        {
            SmtpClient client = GetSmtpClient(configuration);
            MailMessage MailMessageForCancellationAppointment = GetMailMessageToCancelAppointment(userEmail, tooLateToCancel, appointmentCount);

            try
            {
                client.Send(MailMessageForCancellationAppointment);
                return true;
            }
            catch
            {
                return false;
            }
        }

        private static MailMessage GetMailMessageToCancelAppointment(string userEmail, bool tooLateToCancel, int appointmentCount)
        {
            using (StreamReader reader = File.OpenText("EmailTemplate/cancelAppointment.html"))
            {
                string newHtml = "";
                MailMessage mailmessage = new MailMessage();
                mailmessage.IsBodyHtml = true;
                mailmessage.From = new MailAddress("carlmelaniemasso@gmail.com");
                mailmessage.To.Add(new MailAddress(userEmail));
                mailmessage.Subject = "Confirmation d'annulation";
                tooLateToCancel = true;
                var htmlFile = reader.ReadToEnd();
                if (appointmentCount > 1)
                {
                    newHtml = htmlFile.Replace("[AppointmentAmount]", "Vos rendez-vous ont étés annulés avec succès.");
                }
                else
                {
                    newHtml = htmlFile.Replace("[AppointmentAmount]", "Votre rendez-vous à été annulé avec succès.");
                }
                if(tooLateToCancel)
                {
                    newHtml = newHtml.Replace("[TooLateToCancel]", "Cependant, au moins un rendez-vous est dans moins de 24 heures et n'a pu être annulé." +
                        " S'il-vous-plâit veuillez nous appeler au (418) 774-0246 pour l'annulation de ce dernier.");
                }
                else
                {
                    newHtml = newHtml.Replace("[TooLateToCancel]", "");
                }
                mailmessage.Body = newHtml;
                return mailmessage;
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
                new NetworkCredential(configuration.GetSection("EmailAddress").Value, configuration.GetSection("EmailPassword").Value);
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
                var htmlFile = reader.ReadToEnd();
                newHtml = htmlFile.Replace("[AppointmentTimeOfDayx]", requestInfo.TimeOfDay);
                newHtml = newHtml.Replace("[AppointmentDatex]", requestInfo.Date);
                newHtml = newHtml.Replace("[SenderEmailx]", requestInfo.Email);
                newHtml = newHtml.Replace("[OtherInformationx]", requestInfo.MoreInformation != "" ? "<div>Informations supplémentaires  :</div><div class=\"moreInfoBorder\">" + requestInfo.MoreInformation + "</div>" : "");
                newHtml = newHtml.Replace("[SenderPhoneNumberx]", requestInfo.PhoneNumber);
                newHtml = newHtml.Replace("[SenderNamex]", requestInfo.UserName);
                newHtml = newHtml.Replace("[AppointmentTypex]", requestInfo.TypeOfTreatment);
                mailmessage.Body = newHtml;
                return mailmessage;
            }
        }
    }
}
