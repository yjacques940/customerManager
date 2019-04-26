using System;

namespace WebApi.Models
{
    public class Appointment : BaseModel
    {
        public DateTime CreatedOn { get; set; }
        public bool IsNew { get; set; }
        public bool IsConfirmed { get; set; }
        public int IdCustomer { get; set; }
        public int IdTimeSlot { get; set; }
        public string Therapist { get; set; }
    }
}
