using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class RegistrationInformation
    {
        public Address PhysicalAddress { get; set; }
        public Customer Customer { get; set; }
        public User User { get; set; }
        public List<PhoneNumber> PhoneNumbers { get; set; }
    }
}
