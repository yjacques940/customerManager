using System.Linq;
using WebApi.Data;
using WebApi.Models;
using WebApi.DTO;

namespace WebApi.Services
{
    public class ActionTokenService : BaseCrudService<ActionToken>
    {
        public ActionTokenService(IWebApiContext context) : base(context)
        {
        }

        public ActionToken getActionToken(string token)
        {
            return Context.ActionTokens
                    .FirstOrDefault(c => c.IsActive && c.Token == token/* && c.ExpirationDate.Date <= DateTime.Now.Date*/);
        }

        public bool IsValid(ActionTokenInformation actionTokenInfo, ActionToken actionToken)
        {
            if (actionTokenInfo.IdAppointment != null && actionTokenInfo.IdUser != null)
            {
                return actionTokenInfo.IdAppointment == actionToken.IdAppointment
                    && actionTokenInfo.IdUser == actionToken.IdUser;
            }
            else
            {
                if (actionTokenInfo.IdAppointment != null)
                {
                    return actionTokenInfo.IdAppointment == actionToken.IdAppointment;
                }
                else if (actionTokenInfo.IdUser != null)
                {
                    return actionTokenInfo.IdUser == actionToken.IdUser;
                }
            }
            return false;
        }

        public bool RunActionFromToken(ActionToken actionToken)
        {
            switch (actionToken.Action)
            {
                case "ConfirmAppointment":
                    Remove(actionToken.Id);
                    return RunConfirmAppointment(actionToken);
                case "ForgotPassword":
                    return true;
                default:
                    break;
            }
            return false;
        }

        public bool RunConfirmAppointment(ActionToken actionToken)
        {
            Appointment appointment =
                    Context.Appointments.FirstOrDefault(c => c.IsActive && c.Id == actionToken.IdAppointment);
            if (appointment != null)
            {
                appointment.IsConfirmed = true;
                Context.Update(appointment);
                Context.SaveChanges();
                return true;
            }
            return false;
        }
    }
}
