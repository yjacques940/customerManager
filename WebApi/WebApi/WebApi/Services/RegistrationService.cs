using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class RegistrationService : BaseCrudService<Customer>
    {
        public RegistrationService(IWebApiContext context) : base(context)
        {
        }

        public object RegisterNewUser(RegistrationInformation registerInformation)
        {
            Address address = registerInformation.PhysicalAddress;
            Customer customer = registerInformation.Customer;
            User user = registerInformation.User;
            List<PhoneNumber> phoneNumbers = registerInformation.PhoneNumbers;

            
            if (address != null && customer != null && user != null && phoneNumbers.First() != null)
            {
                Context.Add(address);
                Context.SaveChanges();
                customer.IdAddress = address.Id;
                Context.Add(customer);
                Context.SaveChanges();
                user.IdCustomer = customer.Id;
                Context.Add(user);
                foreach (var phone in phoneNumbers)
                {
                    Context.Add(phone);
                }
            }
            Context.SaveChanges();
           return SaveCustomerPhoneNumbers(phoneNumbers,customer);
        }

        private int SaveCustomerPhoneNumbers(List<PhoneNumber> phoneNumbers, Customer customer)
        {
            foreach (var phone in phoneNumbers)
            {
                Context.Add(new CustomerPhoneNumber() { IdPhoneNumber = phone.Id, IdCustomer = customer.Id });
            }
            Context.SaveChanges();
            return customer.Id;
        }
    }
}
