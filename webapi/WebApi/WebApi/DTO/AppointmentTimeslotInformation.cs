using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class AppointmentTimeSlotInformation
    {
        public Appointment AppointmentInfo { get; set; }
        public TimeSlot TimeSlotInfo { get; set; }
    }
}
