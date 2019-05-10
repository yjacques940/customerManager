using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class ResponseInformation
    {
        public int CustomerId { get; set; }
        public List<ResponseFromWebInformation> Responses { get; set; }
    }
}
