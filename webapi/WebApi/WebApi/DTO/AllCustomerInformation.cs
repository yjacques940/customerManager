using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;

namespace WebApi.DTO
{
    public class AllCustomerInformation
    {
        public string FullName { get; set; }
        public DateTime BirthDate { get; set; }
        public string FullAddress { get; set; }
        public string Sex { get; set; }
        public string Email { get; set; }
        public string Occupation { get; set; }
        public List<PhoneNumberAndTypesInformation> PhoneNumbers { get; set; }
        public int IdUser { get; set; } 
    }
}
