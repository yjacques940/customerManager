using System;
using System.Collections.Generic;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;
using WebApi.Validators;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AppointmentsController : BaseReaderController<AppointmentService, Appointment>
    {
 	    private readonly CustomerPhoneNumberService customerPhoneNumberService;
		private readonly IConfiguration configuration;

        public AppointmentsController(WebApiContext context, AppointmentService service,
            CustomerPhoneNumberService customerPhoneNumberService, IConfiguration configuration) : base(service)
        {
			this.customerPhoneNumberService = customerPhoneNumberService;
			this.configuration = configuration;
        }

        [HttpGet, Route("GetByDate/{date}")]
        public ActionResult<IEnumerable<Appointment>> GetAppointmentsByDate(string date)
        {
            return Service.GetAppointmentsByDate(date);
        }

        [HttpGet, Route("AppointmentsAndCustomers")]
        public ActionResult<IEnumerable<CustomerAppointmentInformation>> GetAppointmentAndCustomers()
        {
            return Service.GetAppointmentAndCustomers(customerPhoneNumberService);
        }

        [HttpPost, Route("CheckAppointmentIsAvailable/{appointment}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public ActionResult AddAppointment([FromBody]AppointmentInformation appointment)
        {
            if (appointment == null)
                return BadRequest();
			var newAppointment = Service.CheckAppointmentIsAvailable(appointment);
            if (newAppointment == null)
			    return Conflict();
			newAppointment.IsActive = true;
            var appointmentAdded = Service.AddOrUpdate(newAppointment);
            var user = Service.GetUser(appointmentAdded);
            EmailSender.SendConfirmationEmail(user.Email, Convert.ToDateTime(appointment.AppointmentDateTime), configuration);
            return Ok(appointmentAdded);
        }

        [HttpDelete]
        [Route("{id:int}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public virtual ActionResult Delete(int id)
        {
            if (Service.Remove(id))
                return NoContent();

            return BadRequest();
        }
    }
}
