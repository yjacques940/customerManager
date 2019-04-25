using System;

namespace WebApi.Models
{
    public class ActionToken : BaseModel
    {
        public DateTime CreatedOn { get; set; }
        public Guid Token { get; set; }
        public string Action { get; set; }
        public int IdCustomer { get; set; }
    }
}
