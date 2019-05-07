using WebApi.Models;

namespace WebApi.DTO
{
    public class ActionTokenInformation
    {
        public string Token { get; set; }
        public int? IdUser { get; set; }
        public int? IdAppointment { get; set; }
    }
}