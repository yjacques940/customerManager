using System;

namespace WebApi.Models
{
    public class ActionToken : BaseModel
    {
        public DateTime CreatedOn { get; set; }
        public DateTime ExpirationDate { get; set; }
        public string Token { get; set; }
        public string Action { get; set; }
        public int? IdUser { get; set; }
        public int? IdAppointment { get; set; }
    }
}
