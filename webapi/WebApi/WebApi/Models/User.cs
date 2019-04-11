using System;

namespace WebApi.Models
{
    public class User : BaseModel
    {
        public string Email { get; set; }
        public string Password { get; set; }
        public int Role { get; set; }
        public DateTime CreatedOn { get; set; }
        public DateTime LastLogin { get; set; }
        public int IdCustomer { get; set; }
    }
}
