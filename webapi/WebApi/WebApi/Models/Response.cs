using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class Response : BaseModel
    {
        public int IdCustomer { get; set; }
        public int IdQuestion { get; set; }
        public bool? ResponseBool { get; set; }
        public string ResponseString { get; set; } = "";
        public DateTime CreatedOn { get; set; }
    }
}
