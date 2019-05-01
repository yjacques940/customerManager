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
            var phoneNumbers = Context.PhoneNumbers.Where(c => c.IdCustomer == personalInformation.Customer.Id && c.IsActive);
            personalInformation.PhoneNumbers = new List<PhoneNumber>();
            foreach (var phoneNumber in phoneNumbers)
            {
                personalInformation.PhoneNumbers.Add(Context.PhoneNumbers.First(c => c.Id == phoneNumber.Id));
            }
            return personalInformation;
        }

        internal bool UpdatePersonalInformation(UserUpdatePersonalInformation personalInformation)
        {
            var user = Context.Users.Where(c => c.Id == personalInformation.UserId).FirstOrDefault();
            var customer = Context.Customers.Where(c => c.Id == user.IdCustomer).FirstOrDefault();
            var address = Context.Addresses.Where(c => c.Id == customer.IdAddress).FirstOrDefault();
            var oldPhones = Context.PhoneNumbers.Where(c => c.IdCustomer == customer.Id).ToList();
            if(user != null && customer != null && address != null && oldPhones != null)
            {
                foreach (var phone in oldPhones)
                {
                    Context.Remove(phone);
                }
                customer.Occupation = personalInformation.Occupation;
                address.IdState = personalInformation.PhysicalAddress.IdState;
                address.PhysicalAddress = personalInformation.PhysicalAddress.PhysicalAddress;
                address.ZipCode = personalInformation.PhysicalAddress.ZipCode;
                address.CityName = personalInformation.PhysicalAddress.CityName;
                foreach (var newPhoneNumber in personalInformation.PhoneNumbers)
                {
                    newPhoneNumber.IsActive = true;
                    newPhoneNumber.IdCustomer = customer.Id;
                    Context.Add(newPhoneNumber);
                }
                Context.SaveChanges();
                return true;
            }
            return false;
        }

        internal bool UpdatePersonalInformationWithCustomerId(UpdateCustomerPersonalInformation personalInformation)
        {
            var customer = Context.Customers.FirstOrDefault(c => c.Id == personalInformation.CustomerId);
            var address = Context.Addresses.FirstOrDefault(c => c.Id == customer.IdAddress);
            var oldPhones = Context.PhoneNumbers.Where(c => c.IdCustomer == customer.Id);
            if (customer != null && address != null && oldPhones != null)
            {
                foreach (var phone in oldPhones)
                {
                    Context.Remove(phone);
                }
                customer.Occupation = personalInformation.Occupation;
                address.IdState = personalInformation.PhysicalAddress.IdState;
                address.PhysicalAddress = personalInformation.PhysicalAddress.PhysicalAddress;
                address.ZipCode = personalInformation.PhysicalAddress.ZipCode;
                address.CityName = personalInformation.PhysicalAddress.CityName;
                foreach (var newPhoneNumber in personalInformation.PhoneNumbers)
                {
                    newPhoneNumber.IsActive = true;
                    newPhoneNumber.IdCustomer = customer.Id;
                    Context.Add(newPhoneNumber);
                }
                Context.SaveChanges();
                return true;
            }
            return false;
        }

        internal RegistrationInformation GetPersonalInformationWithCustomerId(int idCustomer)
        {
            var personalInformation = new RegistrationInformation();
            personalInformation.Customer = Context.Customers.First(c => c.Id == idCustomer);
            personalInformation.User = new User();
            personalInformation.PhysicalAddress = Context.Addresses.First(c => c.Id == personalInformation.Customer.IdAddress);
            var phoneNumbers = Context.PhoneNumbers.Where(c => c.IdCustomer == personalInformation.Customer.Id && c.IsActive);
            personalInformation.PhoneNumbers = new List<PhoneNumber>();
            foreach (var phoneNumber in phoneNumbers)
            {
                personalInformation.PhoneNumbers.Add(Context.PhoneNumbers.First(c => c.Id == phoneNumber.Id));
            }
            return personalInformation;
        }
    }
}
