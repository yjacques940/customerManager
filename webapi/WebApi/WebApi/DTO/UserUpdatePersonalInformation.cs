using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class UserUpdatePersonalInformation
    {
        public Address PhysicalAddress { get; set; }
        public int UserId { get; set; }
        public string Occupation { get; set; }
        public List<PhoneNumber> PhoneNumbers { get; set; }
    }
}
