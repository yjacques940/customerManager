using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Validators;

namespace WebApi.Services
{
    public class CustomerService : BaseCrudService<Customer>
    {
        public CustomerService(IWebApiContext context) : base(context)
        {
        }

        public Customer GetCustomerByUserId(int idUser)
        {
            return Context.Customers.First(c => c.Id == Context.Users.First(u => u.Id == idUser).IdCustomer);
        }

        public string GetCustomerFullName(int customerId)
        {
            var customer = Context.Customers.FirstOrDefault(c => c.Id == customerId);
            return customer.FirstName + " " + customer.LastName;
        }

        public int ChangeCustomerLastName(int id, string lastName)
        {
            var customer = Context.Customers.Where(c => c.Id == id).First();
            if (customer != null)
            {
                customer.LastName = lastName;
                Context.Update(customer);
            }

            Context.SaveChanges();
            return customer.Id;
        }

        public int AddNewCustomer(Customer customer)
        {
            //ajouter validation si le user existe déjà
            //devra créer le dernier id automatiquement,
            //aller chercher le dernier +1
            if (customer != null)
            {
                Context.Add(customer);
            }

            Context.SaveChanges();
            //Renvoyer le id du customer qui a été assigné : customer.Id
            return customer.IdAddress;
        }

        public List<Customer> GetCustomersByPhone(string phone)
        {
            List<PhoneNumber> listPhones = Context.PhoneNumbers.Where(c => c.Phone.Contains(phone)).ToList();
            List<Customer> customers = new List<Customer>();
            foreach (var listPhone in listPhones)
            {
                customers.Add(Context.Customers.Where(c => c.Id == listPhone.IdCustomer).First());
            }
            return customers;
        }

        public List<Customer> GetCustomersByName(string name)
        {
            List<Customer> customers = Context.Customers.Where(c => c.LastName.Contains(name) || 
            c.FirstName.Contains(name)|| (c.FirstName + " " + c.LastName).Contains(name)).ToList();
            return customers;
        }

        internal ActionResult<List<CustomerAndPhoneNumberInformation>> GetCustomersWithPhone()
        {
            List<CustomerAndPhoneNumberInformation> customers = new List<CustomerAndPhoneNumberInformation>();
            foreach (var customer in Context.Customers)
            {
                if (customer.IsActive)
                {
                    var newCustomer = new CustomerAndPhoneNumberInformation();
                    newCustomer.Customer = customer;
                    newCustomer.PhoneNumberAndTypes = GetPhoneNumberAndTypes(customer.Id);
                    customers.Add(newCustomer);
                }
            }
            return customers;
        }

        public AllCustomerInformation GetAllCustomerInformationByCustomerId(int customerId)
        {
            var customerInfo = new AllCustomerInformation();
            var user = Context.Users.FirstOrDefault(c => c.IdCustomer == customerId && c.IsActive);
            var customer = Context.Customers.FirstOrDefault(c => c.Id == customerId && c.IsActive);
            
            customerInfo.BirthDate = customer.BirthDate.ToString("yyyy-MM-dd");
            customerInfo.FullName = GetCustomerFullName(customerId);
            customerInfo.Occupation = customer.Occupation;
            customerInfo.Sex = customer.Sex;
            customerInfo.FullAddress = GetCustomerFullAddress(customer.IdAddress);
            customerInfo.PhoneNumbers = GetPhoneNumberAndTypes(customerId);
            customerInfo.Email = user != null ? user.Email : "";
            customerInfo.IdUser = user != null ? user.Id : 0;

            return customerInfo;
        }

        public User GetCustomerIdByUserId(int userId)
        {
            return Context.Users.FirstOrDefault(c => c.Id == userId && c.IsActive);
        }

        internal int CreateUserForCustomer(EmailAndCustomerInfo emailInfo, Microsoft.Extensions.Configuration.IConfiguration configuration, UserService userService)
        {
            var user = new User()
            {
                Password = Guid.NewGuid().ToString(),
                Email = emailInfo.Email,
                IdCustomer = emailInfo.CustomerId,
                IsActive = true,
                CreatedOn = DateTime.Now,
                LastLogin = DateTime.Now
            };
            Context.Add(user);
            Context.SaveChanges();
            userService.SendChangePasswordEmail(configuration,user.Email,true);
            return user.IdCustomer;
        }

        private string GetCustomerFullAddress(int idAddress)
        {
            var address = Context.Addresses.First(c => c.Id == idAddress && c.IsActive);
            var state = Context.States.First(c => c.Id == address.IdState && c.IsActive);
            if(address != null && state != null)
            {
                return $"{address.PhysicalAddress}, {address.CityName}, {state.Code}";
            }
            else
            {
                return "Aucune adresse trouvée";
            }
            
        }

        internal object GetCustomerFollowUps(int customerId)
        {
            var customer = Context.Customers.Where(c => c.Id == customerId).First();
            List<FollowUp> followUps = Context.FollowUps.Where(c => c.IdCustomer == customerId).OrderBy(c => c.CreatedOn).ToList();
            List<FollowUpHeader> followUpsToReturn = new List<FollowUpHeader>();
            foreach (var followUp in followUps)
            {
                FollowUpHeader followUpHeader = new FollowUpHeader();
                followUpHeader.Date = followUp.CreatedOn.Date.ToString();
                followUpHeader.Id = followUp.Id;
                followUpHeader.Summary = followUp.Summary;
                followUpsToReturn.Add(followUpHeader);
            }
            CustomerAndFollowUps customerAndFollowUps = new CustomerAndFollowUps();
            customerAndFollowUps.Customer = customer;
            customerAndFollowUps.FollowUps = followUpsToReturn;
            return customerAndFollowUps;
        }

        private List<PhoneNumberAndTypesInformation> GetPhoneNumberAndTypes(int customerId)
        {
            var query = (from phoneNumber in Context.PhoneNumbers
                join phoneType in Context.PhoneTypes on phoneNumber.IdPhoneType equals phoneType.Id
                where phoneNumber.IdCustomer == customerId && phoneNumber.IsActive && phoneType.IsActive
                select new PhoneNumberAndTypesInformation()
                {
                    Phone = phoneNumber.Phone,
                    PhoneType = phoneType.Name,
                    IdPhoneType = phoneType.Id,
                    Extension = phoneNumber.Extension,
                    IdCustomer = customerId
                }).ToList();
            return query;
        }
    }
}
