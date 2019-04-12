using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class Address : BaseModel
    {
        public string PhysicalAddress { get; set; }
        public string CityName { get; set; }
        public string ZipCode { get; set; }
        public int IdState { get; set; }
    }
}
