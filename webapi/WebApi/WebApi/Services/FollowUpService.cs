using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class FollowUpService : BaseCrudService<FollowUp>
    {
        public FollowUpService(IWebApiContext context) : base(context)
        {
        }

        internal bool AddNewFollowUp(FollowUp followUp)
        {
            if(followUp != null)
            {
                followUp.IsActive = true;
                Context.Add(followUp);
                Context.SaveChanges();
                return true;
            }
            return false;
        }

        internal object GetFollowUpWithId(int id)
        {
            return Context.FollowUps.Where(c => c.Id == id).First();
        }
    }
}
