using System.Collections.Generic;
using System.Linq;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public abstract class BaseCrudService<T> : BaseService
    where T : BaseModel, new()
    {
        protected BaseCrudService(IWebApiContext context)
            : base(context)
        {
        }

        public virtual int AddOrUpdate(T entity)
        {
            var isExistRecord = Context.Set<T>().Any(c => c.Id == entity.Id);

            if (isExistRecord)
                Context.Set<T>().Update(entity);
            else
            {
                entity.IsActive = true;
                Context.Set<T>().Add(entity);
            }

            Context.SaveChanges();
            return entity.Id;
        }

        public virtual bool Remove(int id)
        {
            var entity = Context.Set<T>().Find(id);

            if (entity == null)
                return false;

            entity.IsActive = false;
            Context.SaveChanges();
            return true;
        }

        public virtual bool RemoveRange(List<int> ids)
        {
            ids.ForEach(id =>
            {
                var entity = new T { Id = id };
                Context.Set<T>().Attach(entity);
                entity.IsActive = false;
            });
            Context.SaveChanges();
            return true;
        }

        public virtual T Get(int id)
        {
            return Context.Set<T>().Find(id);
        }

        public virtual List<T> GetList()
        {
            return Context.Set<T>().ToList();
        }
    }
}
