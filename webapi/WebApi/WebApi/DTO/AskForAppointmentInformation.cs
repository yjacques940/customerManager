using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.DTO
{
    public class AskForAppointmentInformation
    {
        public string Date { get; set; }
        public string TimeOfDay { get; set; }
        public string TypeOfTreatment { get; set; }
        public string MoreInformation { get; set; }
        public string Email { get; set; }
        public string UserId { get; set; }
        public string PhoneNumber { get; set; }
        public string UserName { get; set; }
    }
}
