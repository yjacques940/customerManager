using WebApi.Models;
using WebApi.DTO;

namespace WebApi.DTO
{
    public class BasicTimeSlotAppointmentCustomerInformation
    {
        public int IdTimeSlot { get; set; }
        public int IdAppointment { get; set; }
        public string NotesTimeSlot { get; set; }
        public CustomerBasicInformation CustomerInfo { get; set; }
    }
}