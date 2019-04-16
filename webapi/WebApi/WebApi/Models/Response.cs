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
    }
}
