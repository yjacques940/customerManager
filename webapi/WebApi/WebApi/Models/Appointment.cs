using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class Appointment : BaseModel
    {
        public DateTime AppointmentDateTime { get; set; }
        public DateTime DurationTime { get; set; }
        public int IdCustomer { get; set; }
        public bool IsNew { get; set; }
    }
}
