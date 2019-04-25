using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class CustomerAndFollowUps
    {
        public Customer Customer { get; set; }
        public List<FollowUpHeader> FollowUps {get;set;}
    }
}
