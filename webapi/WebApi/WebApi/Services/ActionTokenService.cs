using System.Linq;
using WebApi.Data;
using WebApi.Models;

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
