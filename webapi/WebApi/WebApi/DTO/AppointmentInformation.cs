using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.DTO
{
    public class AppointmentInformation
    {
        public string AppointmentDateTime { get; set; }
        public string DurationTime { get; set; }
        public string IdCustomer { get; set; }
    }
}