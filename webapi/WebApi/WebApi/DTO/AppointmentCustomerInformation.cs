using WebApi.Models;

namespace WebApi.DTO
{
    public class AppointmentCustomerInformation
    {
        public Appointment Appointment { get; set; }
        public CustomerBasicInformation Customer { get; set; }
    }
}