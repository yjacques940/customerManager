using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class PersonalInformationService : BaseCrudService<Customer>
    {
        public PersonalInformationService(IWebApiContext context) : base(context)
        {
        }

        public RegistrationInformation GetRegistrationInformation(int idUser)
        {
            var personalInformation = new RegistrationInformation();
            personalInformation.User = Context.Users.First(c => c.Id == idUser);
            personalInformation.Customer = Context.Customers.First(c => c.Id == personalInformation.User.IdCustomer);
            personalInformation.PhysicalAddress = Context.Addresses.First(c => c.Id == personalInformation.Customer.IdAddress);
            var phoneNumbers = Context.PhoneNumbers.Where(c => c.IdCustomer == personalInformation.Customer.Id);
            personalInformation.PhoneNumbers = new List<PhoneNumber>();
            foreach (var phoneNumber in phoneNumbers)
            {
                personalInformation.PhoneNumbers.Add(Context.PhoneNumbers.First(c => c.Id == phoneNumber.Id));
            }
            return personalInformation;
        }
    }
}
