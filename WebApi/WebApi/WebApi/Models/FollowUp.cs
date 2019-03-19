using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class FollowUp
    { 
        public int Id { get; set; }
        public string Treatment { get; set; }
        public string Summary { get; set; }
        public DateTime CreatedOn { get; set; }
    }
}
