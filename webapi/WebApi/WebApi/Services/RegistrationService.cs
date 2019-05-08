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

        public int RegisterNewUser(RegistrationInformation registerInformation)
        {
            Address address = registerInformation.PhysicalAddress;
            Customer customer = registerInformation.Customer;
            User user = registerInformation.User;
            List<PhoneNumber> phoneNumbers = registerInformation.PhoneNumbers;
            if (address != null && customer != null && user != null && phoneNumbers.First() != null)
            {
                address.IsActive = true;
                customer.IsActive = true;
                user.IsActive = true;
                Context.Add(address);
                Context.SaveChanges();
                customer.IdAddress = address.Id;
                Context.Add(customer);
                Context.SaveChanges();
                if (user.Email != "" && user.Password != "")
                {
                    user.IdCustomer = customer.Id;
                    user.LastLogin = DateTime.Now;
                    user.CreatedOn = DateTime.Now;
                    Context.Add(user);
                }
                Context.SaveChanges();
                foreach (var phone in phoneNumbers)
                {
                    phone.IsActive = true;
                    phone.IdCustomer = customer.Id;
                    Context.Add(phone);
                }
            }
            Context.SaveChanges();
           return customer.Id;
        }
    }
}
