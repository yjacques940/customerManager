using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class CustomerAppointmentInformation 
    {
        public Appointment Appointment { get; set; }
        public Customer Customer { get; set; }
    }
}
