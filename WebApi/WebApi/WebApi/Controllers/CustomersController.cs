using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using WebApi.Data;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class CustomersController : BaseController<CustomerService, Customer>
    {
        public CustomersController(WebApiContext context, CustomerService service) : base(service)
        {
        }

        [HttpGet("GetAll")]
        public ActionResult<IEnumerable<Customer>> GetCustomer()
        {
            return Service.GetList();
        }

        [HttpGet("FullName/{id}")]
        public string GetCustomerFullName(int id)
        {
            return Service.GetCustomerFullName(id);
        }

        [HttpPut("AddNew/{customer}")]
        public ActionResult AddNewCustomerObject(Customer customer)
        {
            return Ok(Service.AddNewCustomer(customer));
        }

        [HttpPut("UpdateCustomerLastName/{id}/{lastName}")]
        public ActionResult UpdateCustomerLastName(int id, string lastName)
        {
            return Ok(Service.ChangeCustomerLastName(id, lastName));
        }

        [HttpDelete("Delete/{id}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult DeleteCustomer(int id)
        {
            if (Service.Remove(id))
                return NoContent();

            return BadRequest();
        }
    }
}
