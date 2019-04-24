using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class CustomerService : BaseCrudService<Customer>
    {
        public CustomerService(IWebApiContext context) : base(context)
        {
        }

        public string GetCustomerFullName(int customerId)
        {
            var customer = Context.Customers.Where(c => c.Id == customerId);
            return customer.First().FirstName + " " + customer.First().LastName;
        }

        public int ChangeCustomerLastName(int id, string lastName)
        {
            var user = Context.Customers.Where(c => c.Id == id).First();
            if (user != null)
            {
                user.LastName = lastName;
                Context.Update(user);
            }

            Context.SaveChanges();
            return user.Id;
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
            List<int> listPhoneIds = Context.PhoneNumbers.Where(c => c.Phone.Contains(phone)).Select(c => c.Id).ToList();
            List<Customer> customers = Context.Customers.Where(c => listPhoneIds.Contains(c.Id)).ToList();
            return customers;
        }

        public List<Customer> GetCustomersByName(string name)
        {
            List<Customer> customers = Context.Customers.Where(c => c.LastName.Contains(name) || c.FirstName.Contains(name)).ToList();
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
